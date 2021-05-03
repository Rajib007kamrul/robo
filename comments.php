<?php
    if ( post_password_required() ) {
        return;
    }
?>

<div class="col-md-12">
    <div class="comment_area_sec">
        <div class="row no-gutters">
            <div class="col-md-12">
<?php
    if ( comments_open() || pings_open() ) {
        comment_form(
            array(
                'class_form'         => 'section-inner thin max-percentage',
                'title_reply_before' => '<h5 class="card-header">',
                'title_reply_after'  => '</h5>',
                'label_submit'       => __( 'Submit' ),
                'class_submit'       => 'btn btn-primary',
                'title_reply'        =>__( 'Leave a Comment' ),
                'title_reply_to'        =>__( 'Leave a Comment to %s' ),
            )
        );
    }

    if ( $comments ) {
        wp_list_comments(
            array(
                'walker'      => new Robo_Walker_Comment(),
                'avatar_size' => 120,
                'style'       => 'div',
            )
        );
    } ?>
            </div>
        </div>
    </div><!--/.comment_area_sec-->
</div>