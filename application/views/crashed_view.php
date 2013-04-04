<?php
    $baseurl = base_url();
?>
<div id="wrap">
  <div class="container r3dalign-interstitial">

    <div class="content">

      <div class="row">

        <div class="span12 well interstitial">
          <h3>Query <?=$query_id?></h3>

          <p class="text-error lead">
            There was a problem with your query. The error has been logged and will be examined.
          </p>

          <?php if ( $error_message != -1 ): ?>

          <p class="text-error">
            <?=$error_message?>
          </p>

          <?php endif; ?>

          <a href="<?=$baseurl?>">Submit another query</a>

        </div>


      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->