
<?php 
    
    if( have_rows('contact_info', 'option') ):
    
        while( have_rows('contact_info', 'option') ) : the_row();
            $rs_business_name = get_sub_field('rs_business_name', 'option');
            $rs_website_url = get_sub_field('rs_website_url', 'option');
            $rs_facebook = get_sub_field('rs_facebook', 'option');
            $rs_logo = get_sub_field('rs_logo', 'option');
            $rs_twitter = get_sub_field('rs_twitter', 'option');
    
        endwhile;
    

    
    ?>
    
    
    <script type="application/ld+json">
    {
        "@context": {
            "@vocab": "http://schema.org/"
        },
        "@graph": [{
                "@id": "#MainOrganization",
                "@type": "Organization",
                "name": "<?php echo $rs_business_name ?>",
                "url": "<?php echo $rs_website_url ?>",
                "logo": "<?php echo $rs_logo ?>",
                "sameAs": ["<?php echo $rs_facebook ?>", "<?php echo $rs_twitter ?>"]
            },
        <?php 
            // check if the repeater field has rows of data
            $rows = get_field('rs_location', 'option');
            $row_count = count($rows);   
        if( have_rows('rs_location', 'option') ):
           
            // loop through the rows of data
        while ( have_rows('rs_location', 'option') ) : the_row(); 
            $comma = ','; 
            $rs_type = get_sub_field('rs_type');
            $rs_additional_business_name = get_sub_field('rs_additional_business_name');
            $rs_street_address = get_sub_field('rs_street_address');
            $rs_city = get_sub_field('rs_city');
            $rs_state = get_sub_field('rs_state');
            $rs_zip_code = get_sub_field('rs_zip_code');
            $rs_telephone = get_sub_field('rs_telephone');
            $rs_image = get_sub_field('rs_image');
            $rs_description = get_sub_field('rs_description');
            $rs_latitude = get_sub_field('rs_latitude');
            $rs_longitude = get_sub_field('rs_longitude');
        ?>
            {
                "@type": "<?php echo $rs_type ?>",
                "parentOrganization": {
                    "@id": "#MainOrganization"
                },
                "name": "<?php echo ($rs_additional_business_name ? $rs_additional_business_name :  $rs_business_name )?>",
                "address": {
                    "@type": "PostalAddress",
                    "streetAddress": "<?php echo $rs_street_address ?>",
                    "addressLocality": "<?php echo $rs_city ?>",
                    "addressRegion": "<?php echo $rs_state ?>",
                    "postalCode": "<?php echo $rs_zip_code ?>",
                    "telephone": "<?php echo $rs_telephone ?>"
                },
                "image": "<?php echo $rs_image ?>",
                "description": "<?php echo $rs_description ?>",
            "geo" : {
                "@type" : "GeoCoordinates",
                "latitude" : "<?php echo $rs_latitude ?>",
                "longitude" : "<?php echo $rs_longitude ?>"
            }}<?php 

              if (get_row_index() >= 1 && $row_count != get_row_index()) {
                  echo ',';
              }
            ?>
          
            <?php 
        endwhile;
        
    else :
    
        // no rows found
    
    endif;
    
    ?>
           
        ]
    }
    
    </script>
<?php endif; ?>