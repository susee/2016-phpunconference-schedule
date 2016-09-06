PHP Unconference 2016 schedule
=========================

Looking for the schedule? 
http://bootev.github.io/2016-phpunconference-schedule

If you found a typo or something like that you think should be fixed? Notify @phpunconference
on twitter or create a PR right here.

Installation
------------

Get build dependencies. Frontend dependencies are checked in as they must be provided for
gh-pages branch anyway.

`composer install`

Now you can change the config and build the schedule with the following command

`php build.php phpuc:build`

Configuration
-------------

The schedule is structured in the schedule.yaml. `<index>` is the key for the filename. We
have one for the first and one for the second day of the conference. The name for the link
on the index page is defined by the `title`. Next we define what rooms are available for
that day and the last item in the list is `slots`.  
`rooms` in each slot may contain just one item to signal lunch breaks or the like.

```
<index>:
  title: <Name of the index>
  rooms:
    <1..n array of room names>
  slots:
    <index>:
      time: <Time slot>
      rooms:
        <Array of the same length as rooms with talk titles>
```

Workflow
--------

After building the schedule you need to push the HTML and stuff to the gh-pages branch.
You should use this easy on-liner

`git subtree push --prefix build origin gh-pages`
