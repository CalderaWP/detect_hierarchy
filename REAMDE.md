
<img src="https://calderawp.com/wp-content/uploads/2015/02/CalderaWP_Logo_SQ-150x150.png" >

# Caldera Taxonomy Tools: Detect Hierarchy
Detect the child and/ or parent of a taxonomy being queried for. Designed to be run at `pre_get_posts` but can work with any WP_Query object.

#### By <a href="https://CalderaWP.com" title="CalderaWP: Transform Your WordPress Experience">CalderaWP</a>


### How To Use

```
    <?php
        //Check if current query is for a parent term
        add_action( 'pre_get_posts', function( $query ) {
            $is_parent_term = calderawp\taxonomy_tools\detect_hierarchy::from_query( $query, 'parent' );
            if ( $is_parent_term ) {
                //do something
            }
            
        });
        
        //Check if current query is for a child term
        add_action( 'pre_get_posts', function( $query ) {
            $has_parent = calderawp\taxonomy_tools\detect_hierarchy::from_query( $query, 'child' );
            if ( $has_parent ) {
                //do something
            }
            
        });
        
        //You can also get an array with two keys "parent", "child" whose values are true or false based on if term has parents or children
        add_action( 'pre_get_posts', function( $query ) {
                $hierarchy = calderawp\taxonomy_tools\detect_hierarchy::from_query( $query );
        });
```

By default this will only run on the main query and in the front-end only. The third argument, if set to false, will allow it to run on all queries.  The fourth, if set to false will disable the check of `is_admin()`.


### License, Copyright etc.
Copyright 2014-2015 [CalderaWP LLC](https://CalderaWP.com) & [Josh Pollock](http://JoshPress.net).

Licensed under the terms of the [GNU General Public License version 2](http://www.gnu.org/licenses/gpl-2.0.html) or later. Please share with your neighbor.
