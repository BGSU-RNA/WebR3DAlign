<?php
    $baseurl = base_url();
?>
<div id="wrap">
  <div class="container r3dalign-interstitial">

    <div class="content">

      <div class="row">

        <div class="span12 well interstitial">
          <h3>
            Query <?=$query_id?>
            <small>
              submitted at <?php echo date('H:i:s', strtotime($time_submitted));?>
                        on <?php echo date('Y-m-d', strtotime($time_submitted));?>
            </small>
          </h3>

          <?php if ( $status == 'submitted' ): ?>
            <p class="text-info lead">
              Your job request has been successfully submitted.
            </p>
          <?php else: ?>
            <p class="text-info lead">
              Your job request is being processed.
            </p>
          <?php endif; ?>

          <p>
            This page will automatically refresh every <strong>30 seconds</strong> until the results become available.
            An email notification will be sent if an email address was provided.
          </p>

          <p>
            You can <strong>bookmark</strong> this page and return later to view your results.
            All R3D Align <strong>results are stored indefinitely</strong>, so feel free to
            share the url to refer to these results.
          </p>

          <p>
            Processing usually takes 3-10 minutes.
            After 30 minutes of CPU time all queries are aborted.
            Please consider downloading the <a href="https://github.com/BGSU-RNA/R3DAlign" target="_blank">standalone R3D Align program</a>
            for computationally-intensive tasks.
          </p>

          <a href="<?=$baseurl?>" target="_blank">Submit another query</a>

          <br><br>

          <!-- AddThis Button BEGIN -->
          <span class="addthis_toolbox addthis_default_style">
          <a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=ra-4fb19f2d543502ad" class="addthis_button_compact at300m"><span class="at16nc at300bs at15nc at15t_compact at16t_compact"><span class="at_a11y">More Sharing Services</span></span>Share</a>
          <div class="atclear"></div>
          </span>
          <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
          <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4fb19f2d543502ad"></script>
          <!-- AddThis Button END -->

        </div>



      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->

<script>

$(function() {
	setTimeout("location.reload(true);", 30000);
});


</script>
