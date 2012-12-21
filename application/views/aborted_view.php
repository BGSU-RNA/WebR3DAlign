<?php
    $baseurl = base_url();
?>
<div id="wrap">
  <div class="container r3dalign-interstitial">

    <div class="content">

      <div class="row">

        <div class="span12 well interstitial">
          <h3>Thank you for using R3D Align.</h3>

          <p class="text-error">
            Your job request has been aborted because it took longer than 30 minutes.
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