FacetWP - Proximity Facet
=======================

A [FacetWP](https://facetwp.com/) facet for filtering posts by proximity. Uses the [Google Places API](https://developers.google.com/places/documentation/autocomplete) to display an autocomplete search box.

![screenshot](http://i.imgur.com/zWolfCb.png)

## Requirements
* FacetWP 1.4.0 or higher
* [Address Geocoder](http://wordpress.org/plugins/address-geocoder/) 0.6 or higher
* WordPress 3.8 or higher

## Installation
* Click the "Download ZIP" button on this page.
* Unzip the folder and rename it to "facetwp-proximity"
* Upload the folder into the /wp-content/plugins/ directory
* Activate the plugin

## Setup
* Use the Address Geocoder plugin to add location info to your posts. When editing a post, you'll see a "Geocoder" meta box. Enter an address,  hit "Geocode", then save the post.
* Create a new facet. Select `Proximity` for the Facet Type, and `martygeocoderlatlng` for the Data Source. Save, then click Rebuild Index.
* Add the facet to your page using the `[facetwp facet="FACETNAME"]` shortcode.
