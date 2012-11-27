<div id="wrap">
  <div class="container r3dalign-homepage">
    <div class="content">

    <div class="row well well-small small"> <!-- row structure 1 -->

      <p>
       <span class="label label-info">Instructions</span>
        Specify two RNA 3D structures that you would like to superimpose and hit submit.
        You will be redirected to an interstitial page that you can bookmark.
        Processing usually takes 3-10 minutes.
        You can leave your email address if you wish to be notified once the results are ready.
        Take a look at <a href="">help</a> and <a href="">tutorial</a>
        and feel free to <a href="">contact us</a> if you have questions.
      </p>

      <p>
        <span class="label label-info">New feature</span>
        R3DAlign is integrated with the <a href="http://rna.bgsu.edu/rna3dhub/nrlist">Non-redundant Atlas</a>
        of RNA 3D structures.
      </p>

    </div>

    <form>

    <!-- structure 1 -->
    <div class="row well well-small">

      <div class="row">
        <h4 class="span12">First Structure</h4>
      </div>

      <div class="row">

        <!-- pdb selection -->
        <div class="span5">

          <select class="pdb1 span2" tabIndex="1" data-placeholder="Choose PDB id">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>

          or upload a file

          <br>

          <input type="file" name="upload_pdb1" id="upload_pdb1" size="20" />

        </div> <!-- pdb selection -->

        <!-- fragment selection -->
        <div class="span7 mol1_fragments"></div>

      </div>

      <!-- structure 1 info -->
      <div class="small mol1"></div>

    </div> <!-- structure 1 -->

    <!-- structure 2 -->
    <div class="row well well-small">

      <div class="row">
        <h4 class="span12">Second Structure</h4>
      </div>

      <!-- structure 2 controls -->
      <div class="row">

        <!-- pdb selection -->
        <div class="span5">

          <select class="pdb2 span2" tabIndex="2" data-placeholder="Choose PDB id">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>

          or upload a file

          <br>

          <input type="file" name="upload_pdb2" id="upload_pdb2" size="20" />

        </div> <!-- pdb selection -->

        <!-- fragment selection -->
        <div class="span7 mol2_fragments"></div>

      </div> <!-- structure 2 controls -->

      <!-- structure 2 info -->
      <div class="small mol2"></div>

    </div> <!-- row structure 2 -->

   <!-- Advanced options-->
    <div class="advanced-options row">
    <div class="row well well-small iteration1 span12" id="iteration1">
      <fieldset>
        <div class="span3">
          <strong>Iteration 1</strong><br>

          <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy1" name="discrepancy1">
          <span class="help-inline">
            Discrepancy (d)
            <i class="icon-info-sign" data-original-title="Enter a number between 0 and 0.7"></i>
          </span>
          <br>

          <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods1" name="neighborhoods1">
          <span class="help-inline">
            Neighborhoods (p)
            <i class="icon-info-sign" data-original-title="Enter an integer between 2 and 10"></i>
          </span>
          <br>

          <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth1" name="bandwidth1">
          <span class="help-inline">
            Alignment bandwidth (&beta;)
            <i class="icon-info-sign" data-original-title="Enter a positive integer"></i>
          </span>
          <br>
        </div>

        <div class="span3">
          <strong>Final clique-finding method:</strong>
          <i class="icon-info-sign"></i>
          <label class="radio">
            <input type="radio" name="clique_method1" value="greedy" id="clique_method_greedy1" checked>
            Greedy (Faster)
          </label>
          <label class="radio">
            <input type="radio" name="clique_method1" value="full" id="clique_method_full1">
            Branch and Bound (Exact)
          </label>
        </div>

        <div class="span3">
          <strong>Seed alignment:</strong>
          <i class="icon-info-sign"></i>
          <label class="radio">
            <input type="radio" name="seed1" id="seed_default1" value="NWseed" checked>
              Internally produced alignment
          </label>
          <label class="radio">
            <input type="radio" name="seed1" id="seed_upload1" value="Manual">
            Upload seed alignment (fasta)
          </label>
          <input type="file" name="upload_seed1" id="upload_seed1" size="20" />
        </div>

        <div class="span2">
          <label class="checkbox">
            <input type="checkbox" id="toggle_iteration2"> Use this alignment as the seed for next iteration?
          </label>
        </div>

      </fieldset>
    </div>

    <div class="row well well-small iteration2 span12" id="iteration2">
      <fieldset>
      <div class="span3">
        <strong>Iteration 2</strong><br>
        <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy2" name="discrepancy2">
        <span class="help-inline">Discrepancy (d)</span>
        <br>

        <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods2" name="neighborhoods2">
        <span class="help-inline">Neighborhoods (p)</span>
        <br>

        <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth2" name="bandwidth2">
        <span class="help-inline">Alignment bandwidth (&beta;)</span>
      </div>

      <div class="span3">
        <strong>Final clique-finding method:</strong>
        <label class="radio">
          <input type="radio" name="clique_method2" value="greedy" id="clique_method_greedy2" checked>
          Greedy (Faster)
        </label>
        <label class="radio">
          <input type="radio" name="clique_method2" value="full" id="clique_method_full2">
          Branch and Bound (Exact)
        </label>
      </div>

      <div class="span3">
        <strong>Seed alignment:</strong>
        <label class="radio">
          <input type="radio" name="seed2" id="seed_default2" value="NWseed" checked>
          Internally produced alignment
        </label>
        <label class="radio">
          <input type="radio" name="seed2" id="seed_upload2" value="Manual">
          Upload seed alignment (fasta)
        </label>
        <input type="file" name="upload_seed2" id="upload_seed2" size="20" />
      </div>

      <div class="span2">
        <label class="checkbox">
          <input type="checkbox" id="toggle_iteration3"> Use this alignment as the seed for next iteration?
        </label>
      </div>
      </fieldset>
   </div>

   <div class="row well well-small iteration3 span12" id="iteration3">
     <fieldset>
       <div class="span3">
         <strong>Iteration 3</strong><br>
         <input type="text" class="r3dalign-input-mini" value="0.5" id="discrepancy3" name="discrepancy3">
         <span class="help-inline">Discrepancy (d)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="7" id="neighborhoods3" name="neighborhoods3">
         <span class="help-inline">Neighborhoods (p)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="60" id="bandwidth3" name="bandwidth3">
         <span class="help-inline">Alignment bandwidth (&beta;)</span>
        </div>

        <div class="span3">
          <strong>Final clique-finding method:</strong>
          <label class="radio">
            <input type="radio" name="clique_method3" value="greedy" id="clique_method_greedy3" checked>
            Greedy (Faster)
          </label>
          <label class="radio">
            <input type="radio" name="clique_method3" value="full" id="clique_method_full3">
            Branch and Bound (Exact)
          </label>
        </div>

      </fieldset>
    </div>

    </div> <!-- advanced options -->

    <div class="row well well-small form-inline"> <!-- form controls -->
      <button type="button" class="btn" id="toggle_advanced">Show advanced options</button>
      <button type="button" class="btn" id="reset">Reset</button>

      <div class="btn-group dropup">
        <a class="btn dropdown-toggle small" data-toggle="dropdown" href="#">
          Examples
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a id="16s">16S</a></li>
          <li><a id="5s_partial">5S rRNA (partial chains)</a></li>
          <li><a id="5s_complete">5S rRNA (complete chains)</a></li>
          <li><a id="rnase_p">RNase P</a></li>
        </ul>
      </div>

      <input type="text" placeholder="Email (optional)" id="email" name="email">
      <span class="alert alert-info small results"></span>
      <button type="submit" class="btn btn-primary span2 pull-right disabled" id="submit">Submit</button>
    </div> <!-- row form controls -->


    </form>
    </div> <!-- content -->
  </div> <!-- container -->
</div> <!-- wrap -->


<script type="text/javascript" src="<?php echo base_url(); ?>css/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/main.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/homepage.js"></script>


<script>

$(function() {
   $('form').submit(function(e) {
      e.preventDefault();
      $.ajaxFileUpload({
         url         :'<?php echo base_url(); ?>/upload/upload_file/',
         secureuri      :false,
         fileElementId  :'upload1',
         dataType    : 'json',
         data        : {
            'title'           : 'test'
         },
         success  : function (data, status)
         {
            if(data.status != 'error')
            {
               $('.results').html('Done');
            }
            alert(data.msg);
         }
      });
      return false;
   });
});


$(function() {

    $(".pdb1").chosen().change(function(){
        var div = ".mol1";
        var pdb_id = this.options[this.selectedIndex].text;
        UTIL.load_structure_data(div, pdb_id);
    });

    $(".pdb2").chosen().change(function(){
        var div = ".mol2";
        var pdb_id = this.options[this.selectedIndex].text;
        UTIL.load_structure_data(div, pdb_id);
    });

    UTIL.bind_events();

    $('.icon-info-sign').tooltip();

});

</script>