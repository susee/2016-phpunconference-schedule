<?php

namespace PhpUnconf\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Command to build schedule HTML.
 * uses YAML schedule as input and produces finished HTML.
 */
class BuildCommand extends Command
{
    const OPT_OUTPUT_DIR    = "out-dir";
    const OPT_TEMPLATE_DIR  = "template-dir";
    const OPT_TEMPLATE_NAME = "template-file";
    const OPT_CONFIG        = "config";

    /**
     * Configure command
     */
    protected function configure()
    {
        $this->setName('phpuc:build');
        $this->setDescription('Build the PHP Unconference schedule.');

        $this->addOption(self::OPT_OUTPUT_DIR, 'o', InputOption::VALUE_REQUIRED, 'Directory to put finished HTML', './build');
        $this->addOption(self::OPT_TEMPLATE_DIR, null, InputOption::VALUE_REQUIRED, 'Directory to read templates from', './template');
        $this->addOption(self::OPT_TEMPLATE_NAME, null, InputOption::VALUE_REQUIRED, 'Template name for schedule page', 'schedule.twig');
        $this->addOption(self::OPT_CONFIG, 'c', InputOption::VALUE_REQUIRED, 'Path to config', './schedule.yml');
    }

    /**
     * Execute command.
     * First read schedule file.
     * Render template to output directory
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \LogicException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outDir       = $input->getOption(self::OPT_OUTPUT_DIR);
        $templateDir  = $input->getOption(self::OPT_TEMPLATE_DIR);
        $templateName = $input->getOption(self::OPT_TEMPLATE_NAME);
        $configFile   = $input->getOption(self::OPT_CONFIG);

        if (!is_readable($configFile)) {
            throw new \LogicException(sprintf('Unable to read config from file `%s`', $configFile));
        }
        if (!is_writable($outDir)) {
            throw new \LogicException(sprintf('Unable to write html to `%s`', $outDir));
        }

        // read and parse yaml file
        $value = Yaml::parse($configFile);

        // prepare twig
        $loader = new \Twig_Loader_Filesystem($templateDir);
        $twig = new \Twig_Environment($loader, array('debug' => true));

        // render each first level config key as schedule file
        $schedules = array();
        foreach ($value as $dayName => $dayConfig) {
            // generate filename and file path
            $fileName = sprintf('%s.html', $dayName);
            $filePath = sprintf('%s/%s', $outDir, $fileName);

            try {
                $fileContent = $twig->render($templateName, $dayConfig);
                file_put_contents($filePath, $fileContent);

                // add to overview list
                $schedules[] = array(
                    'file' => $fileName,
                    'name' => $dayConfig['title'],
                );
            } catch (\Twig_Error $e) {
                $output->writeln(sprintf('<error>Twig error:</error> %s', $e->getMessage()));
            }
        }

        // render index page
        try {
            $fileContent = $twig->render('index.twig', array('schedules' => $schedules));
            $filePath = sprintf('%s/index.html', $outDir);
            file_put_contents($filePath, $fileContent);
        } catch (\Twig_Error $e) {
            $output->writeln(sprintf('<error>Twig error:</error> %s', $e->getMessage()));
        }
    }
}
