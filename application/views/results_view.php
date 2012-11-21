  <div class="container">
    <div class="content">

      <h3>
        Results xxxx
        <small>
          test
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
              Download
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <!-- dropdown menu links -->
              <li><a href="#">3D alignment (.pdb)</a></li>
              <li><a href="#">3D alignment (.fasta)</a></li>
              <li><a href="#">Alignment spreadsheet (.csv) </a></li>
              <li><a href="#">Bar diagram (.pdf)</a></li>
            </ul>
          </div>
        </small>
      </h3>

      <div class="row span12">

        <ul class="nav nav-tabs" id="nav">
          <li class="active"><a href="#home">Home</a></li>
          <li><a href="#profile">next</a></li>
          <li><a href="#messages">third</a></li>
        </ul>

      <div class="tab-content">
        <div class="tab-pane active" id="home">
        <div class="span6">
          <div class="block-div jmolheight">
            <script type="text/javascript">
              jmolInitialize(" /jmol");
              jmolSetAppletColor("#f3f3f3");
              jmolApplet(500, "load <?php echo base_url(); ?>data/pdb/4d24d95bee03d/4d24d95bee03d.pdb");
            </script>
          </div>
        </div>

        <div class="span5">
          <h4>Bar diagram</h4>
          <ul class="thumbnails">
            <li class="span5">
              <a href="#" class="thumbnail">
                <img src="<?php echo base_url(); ?>data/bar_diagrams/4d24d95bee03d/4d24d95bee03d.jpg">
              </a>
            </li>
          </ul>
        </div>

        </div>
        <div class="tab-pane" id="profile"><?=$data;?></div>
        <div class="tab-pane" id="messages">test3</div>
      </div>
    </div>


    </div> <!-- content -->


<script>
$('#nav a').click(function (e) {
  e.preventDefault();
  $(this).tab('show');
})
</script>
