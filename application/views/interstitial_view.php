<?php
    $baseurl = base_url();
?>
<div id="wrap">
  <div class="container r3dalign-interstitial">

    <div class="content">

      <div class="row">

        <div class="span12 well interstitial">
          <h3>Thank you for using R3D Align.</h3>

          <p>
            Your job request has been successfully submitted.
            This page will automatically refresh every <strong>10 seconds</strong> until the results become available.
            An email notification will be sent if an email address was provided.
          </p>

          <p>
            You can <strong>bookmark</strong> this page and return later to view your results.
            All R3DAlign results are stored indefinitely, so feel free to
            share the url to refer to these results.
          </p>

          <p>
            Processing usually takes 3-10 minutes.
            After 20 minutes all queries are aborted.
            Please consider downloading the <a href="#">standalone R3DAlign program</a>
            for computationally-intensive tasks.
          </p>

          <a href="<?=$baseurl?>">Submit another query</a>

        </div>



      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->

<script>

$(function() {
	setTimeout("location.reload(true);", 10000);
});


</script>
