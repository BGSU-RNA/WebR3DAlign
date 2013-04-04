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
            Your job request has been aborted because it took longer than 30 minutes of CPU time.
          </p>

          <p>
            Please consider downloading the <a href="https://github.com/BGSU-RNA/R3DAlign">standalone R3DAlign program</a>
            for computationally-intensive tasks.
          </p>

          <a href="<?=$baseurl?>">Submit another query</a>

        </div>


      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->