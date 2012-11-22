<?php
    $baseurl = base_url();
?>
<div id="wrap">
  <div class="container r3dalign-results">

    <div class="content">

      <div class="row">

        <div class="span6"> <!-- left panel -->

          <h3>Query <?=$query_id?></h3>

          <script type="text/javascript">
            jmolInitialize(" /jmol");
            jmolSetAppletColor("#f3f3f3");
            jmolApplet(450, "load <?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.pdb; hide a1l; spacefill off; frame all; select 1.1; color lime; select 1.2; color darkolivegreen; select 1.3; color red; select 1.4; color darkred; select all; display all;");
          </script>

          <button class="btn" id="toggle_stereo">Stereo</button>
          <button class="btn" id="jmol_rotate_y">Rotate</button>
          <button class="btn" id="toggle_labels">Labels on</button>
          <button class="btn" id="toggle_aligned">Hide unaligned nucleotides</button>

        </div> <!-- span6, left panel -->

        <div class="span6" id="tabbed_content"> <!-- right panel -->

            <!-- tab menu -->
            <ul class="nav nav-tabs" id="nav">
              <li class="active"><a href="#overview">Overview</a></li>
              <li><a href="#basepairs">Basepairs</a></li>
              <li><a href="#alignment">Alignment</a></li>

              <li class="dropdown">
                <a class="dropdown-toggle" role="button" data-toggle="dropdown" href="#">
                Download <b class="caret"></b></a>
                <ul class="dropdown-menu" role="menu">
                  <?php if (file_exists("/Servers/rna.bgsu.edu/r3dalign_dev/data/results/$query_id/$query_id.pdb")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.pdb"
                           target='_blank' download="<?=$query_id?>.pdb">3D alignment (.pdb)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists("/Servers/rna.bgsu.edu/r3dalign_dev/data/results/$query_id/$query_id.fasta")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.fasta"
                           target='_blank' download="<?=$query_id?>.fasta">3D alignment (.fasta)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists("/Servers/rna.bgsu.edu/r3dalign_dev/data/results/$query_id/$query_id.pdf")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.pdf"
                           target='_blank' download="<?=$query_id?>.pdf">Bar diagram (.pdf)</a>
                    </li>
                  <?php endif; ?>

                  <?php if (file_exists("/Servers/rna.bgsu.edu/r3dalign_dev/data/results/$query_id/$query_id.csv")): ?>
                    <li><a href="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.csv"
                           target='_blank' download="<?=$query_id?>.csv">Basepair comparison (.csv)</a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
            </ul>

            <!-- tab content -->
            <div class="tab-content">

              <!-- overview panel -->
              <div class="tab-pane active" id="overview">
                <h4>
                  Local geometric similarity
                  <small>
                    <i class="icon-info-sign" rel="tooltip"
                    data-original-title="Low geometric discrepancy (blue) indicates high 3D similarity. High discrepancy (red) means low 3D similarity"></i>
                  </small>
                </h4>

                <ul class="thumbnails">
                  <li>
                    <a href="#" class="thumbnail">
                      <img src="<?=$baseurl?>data/results/<?=$query_id?>/<?=$query_id?>.jpg" class="fancybox r3dalign-results-crop">
                    </a>
                  </li>
                </ul>

                <dl>
                  <dt>Molecule 1 (green)</dt>
                  <dd>1J5E, chain A</dd>
                  <dt>Molecule 2 (red)</dt>
                  <dd>2AVY, chain A</dd>
                  <dt>Iteration 1</dt>
                  <dd>d = 0.5, p = 3, &beta; = 60, Heuristic Clique Method</dd>
                  <dt>Iteration 2</dt>
                  <dd>d = 0.5, p = 9, &beta; = 20, Heuristic Clique Method</dd>
                </dl>
              </div> <!-- overview panel -->

              <div class="tab-pane" id="basepairs">
                <table class="table table-bordered table-condensed basepair-table">
                  <?=$basepair_table;?>
                </table>
              </div>

              <div class="tab-pane" id="alignment">
                <pre><?=$alignment?></pre>
              </div>
          </div> <!-- tab-content -->
        </div> <!-- span6, right panel -->
      </div> <!-- row -->

    </div> <!-- content -->

  </div> <!-- container -->
</div> <!-- wrap -->


<script>
    // activate tooltips
    $('i').tooltip()

    $('.fancybox').fancybox({
        afterClose: function(){
            $('img').css('display', 'inline');
        },
        wrapCSS: 'r3dalign-results-crop',
        height: '500',
        autoSize: false
    });

    // activate tab navigation
    $('#nav>li>a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });

    $('#toggle_stereo').toggle(function(){
        jmolScript("stereo on");
    }, function(){
        jmolScript("stereo off");
    });

    $('#toggle_aligned').toggle(function(){
        jmolScript("frame all; display displayed and not 1.2; display displayed and not 1.4;");
        $(this).html('Show unaligned nucleotides');
    }, function(){
        jmolScript("frame all; display displayed or 1.2; display displayed or 1.4;");
        $(this).html('Hide unaligned nucleotides');
    });

    $('#toggle_labels').toggle(function(){
        jmolScript('select *.C5;label "%n%R";color labels black;');
        $(this).html('Labels off');
    }, function() {
        jmolScript("labels off");
        $(this).html('Labels on');
    });

    $('#jmol_rotate_y').on('click', function(){
        jmolScript("move 0 360 0 0 0 0 0 0 2");
    });
</script>
