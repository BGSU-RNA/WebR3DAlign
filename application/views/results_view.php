  <div class="container r3dalign-results">
    <div class="content">

      <h3>
        Query <?=$query_id?>
        <small>
          test
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle btn-primary" data-toggle="dropdown" href="#">
              Download
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">3D alignment (.pdb)</a></li>
              <li><a href="#">3D alignment (.fasta)</a></li>
              <li><a href="#">Alignment spreadsheet (.csv) </a></li>
              <li><a href="#">Bar diagram (.pdf)</a></li>
            </ul>
          </div>
        </small>
      </h3>

      <div class="row">

        <div class="span6">
          <script type="text/javascript">
            jmolInitialize(" /jmol");
            jmolSetAppletColor("#f3f3f3");
            jmolApplet(450, "load <?php echo base_url(); ?>data/results/<?=$query_id?>/<?=$query_id?>.pdb; hide a1l; spacefill off; frame all; select 1.1; color lime; select 1.2; color darkolivegreen; select 1.3; color red; select 1.4; color darkred; select all; display all;");
            jmolBr();
            jmolLink("move 0 360 0 0 0 0 0 0 2", "Rotate once about the Y axis");
          </script>

          <label class="checkbox">
            <input type="checkbox" id="toggle_stereo"> Stereo
          </label>

          <label class="checkbox">
            <input type="checkbox" id="toggle_aligned"> Hide unaligned nucleotides
          </label>

          <label class="checkbox">
            <input type="checkbox" id="toggle_labels"> Nucleotide numbers
          </label>
        </div> <!-- span6 -->

        <div class="span6">
            <ul class="nav nav-tabs" id="nav">
              <li class="active"><a href="#overview">Overview</a></li>
              <li><a href="#basepairs">Basepair comparison</a></li>
              <li><a href="#alignment">Alignment</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane active" id="overview">
                <h4>
                  Bar diagram
                  <small>
                    <i class="icon-info-sign" rel="tooltip"
                    data-original-title="Bar diagram help text here"></i>
                  </small>
                </h4>

                <ul class="thumbnails">
                  <li>
                    <a href="#" class="thumbnail">
                      <img src="<?php echo base_url(); ?>data/bar_diagrams/<?=$query_id?>/<?=$query_id?>.jpg">
                    </a>
                  </li>
                </ul>

                <dl>
                  <dt>Molecule 1 (green)</dt>
                  <dd>1J5E, chain A</dd>
                  <dt>Molecule 2 (red)</dt>
                  <dd>2AVY, chain A</dd>
                  <dt>Iteration 1</dt>
                  <dd>d = 0.5,  p = 3,  β = 60,  Heuristic Clique Method</dd>
                  <dt>Iteration 2</dt>
                  <dd>d = 0.5,  p = 9,  β = 20,  Heuristic Clique Method</dd>
                </dl>
              </div>

              <div class="tab-pane" id="basepairs">
                <table class="table table-bordered table-condensed basepair-table">
                  <?=$basepair_table;?>
                </table>
              </div>

              <div class="tab-pane" id="alignment">
                <pre><?=$alignment?></pre>
              </div>
          </div> <!-- tab-content -->
        </div> <!-- span6 -->
      </div> <!-- row -->

    </div> <!-- content -->


<script>
    // activate tooltips
    $('i').tooltip()

    // activate tab navigation
    $('#nav a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    $('#toggle_stereo').on('click', function(){
        if ( this.checked ) {
            jmolScript("stereo on");
        } else {
            jmolScript("stereo off");
        }
    });

    $('#toggle_aligned').on('click', function(){
        if ( this.checked ) {
            jmolScript("frame all; display displayed and not 1.2; display displayed and not 1.4;");
        } else {
            jmolScript("frame all; display displayed or 1.2; display displayed or 1.4;");
        }
    });

    $('#toggle_labels').on('click', function(){
        if ( this.checked ) {
            jmolScript('select *.C5;label "%n%R";color labels black;');
        } else {
            jmolScript("labels off");
        }
    });
</script>
