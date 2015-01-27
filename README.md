# Instagrizzle
> An Instagram Plugin for Statamic  
  Ported from [Jack McDade's Statamic plugin of the same name](https://github.com/jackmcdade/instagrizzle).

Fetch media from a public Instagram feed, without the need for the API. Yeah, it's a down and dirty scraping plugin. Enjoy!

## The Tag

```
{exp:instagrizzle username="jackmcdade" limit="5" offset="1"}
  <img src="{images:high_resolution:url}">
{/exp:instagrizzle}
```

## The Parameters

### Username `username`

Instagram username of the feed you want to pull.

```
username="jackmcdade"
```

### Limit `limit`

Limit the items returned.
```
limit="5"
```

### Offset `offset`

Offset the items returned.
```
offset="1"
```

### Debug

There are a lot of variables to access from the Instagram response object. You can use the debug parameter to explore the data available to you.

```
debug="yes"
```

### Caching

You should leverage EE's tag caching. `refresh` would be the number of minutes.

```
cache="yes" refresh="30"
```
