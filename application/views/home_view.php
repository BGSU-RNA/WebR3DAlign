<div id="wrap">
  <div class="container r3dalign-homepage">
    <div class="content">

    <div class="row"> <!-- row structure 1 -->
      <div class="alert alert-info">
       <i class="icon-info-sign"></i>
        Specify two RNA 3D structures that you would like to superimpose and hit submit.
        You will be redirected to an interstitial page that you can bookmark.
        You can leave your email address if you wish to be notified once the results are ready.
      </div>
    </div>

    <form>

    <div class="row well well-small"> <!-- row structure 1 -->

      <div class="row">
        <h4 class="span12">First Structure</h4>
      </div>

      <div class="row"> <!-- structure 1 controls -->

        <div class="span5"> <!-- pdb selection -->
          <select class="pdb1 span3" data-placeholder="Choose a structure from PDB">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>
          or <button class="btn" id="upload1">upload PDB file</button>
        </div> <!-- pdb selection -->

        <div class="span7 mol1_info_fragments"></div> <!-- fragment selection -->

      </div> <!-- structure 1 controls -->

      <div class="small mol1_info"></div> <!-- structure 1 info -->

    </div> <!-- row structure 1 -->

    <div class="row well well-small"> <!-- row structure 2 -->

      <div class="row">
        <h4 class="span12">Second Structure</h4>
      </div>

      <div class="row"> <!-- structure 2 controls -->

        <div class="span5"> <!-- pdb selection -->
          <select class="pdb2 span3" data-placeholder="Choose a structure from PDB">
            <option></option>
            <?php foreach ($pdbs as $pdb): ?>
              <option value="<?=$pdb?>"><?=$pdb?></option>
            <?php endforeach; ?>
          </select>
          or <button class="btn" id="upload2">upload PDB file</button>
        </div> <!-- pdb selection -->

        <!-- fragment selection -->
        <div class="span7 mol2_info_fragments"></div>

      </div> <!-- structure 2 controls -->

      <!-- structure 2 info -->
      <div class="small mol2_info"></div>

    </div> <!-- row structure 2 -->

   <!-- Advanced options-->
   <div class="advanced-options row">
   <div class="row well well-small iteration1 span12" id="iteration1">
     <fieldset>
       <div class="span3">
         <strong>Iteration 1</strong><br>

         <input type="text" class="r3dalign-input-mini" value="0.5">
         <span class="help-inline">
           Discrepancy (d)
           <i class="icon-info-sign" data-original-title="Enter a number between 0 and 0.7"></i>
         </span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="7">
         <span class="help-inline">
           Neighborhoods (p)
           <i class="icon-info-sign" data-original-title="Enter an integer between 2 and 10"></i></span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="60">
         <span class="help-inline">
           Alignment bandwidth (&beta;)
           <i class="icon-info-sign" data-original-title="Enter a positive integer"></i>
         </span>
          <br>
        </div>
        <div class="span3">
            <strong>Final clique-finding method:</strong> <i class="icon-info-sign"></i>
            <label class="radio">
              <input type="radio" name="cliqueMethod1" value="greedy" checked>
              Greedy (Faster)
            </label>
            <label class="radio">
              <input type="radio" name="cliqueMethod1" value="full">
              Branch and Bound (Exact)
            </label>
        </div>
        <div class="span3">
            <strong>Seed alignment:</strong> <i class="icon-info-sign"></i>
            <label class="radio">
              <input type="radio" name="seed1" id="optionsRadios1" value="NWseed" checked>
              Internally produced alignment
            </label>
            <label class="radio">
              <input type="radio" name="seed1" id="optionsRadios2" value="Manual">
              Upload seed alignment (fasta):
              <button class="btn">Upload</button>
            </label>
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

         <input type="text" class="r3dalign-input-mini" value="0.5">
         <span class="help-inline">Discrepancy (d)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="7">
         <span class="help-inline">Neighborhoods (p)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="60">
         <span class="help-inline">Alignment bandwidth (&beta;)</span>
        </div>
        <div class="span3">
            <strong>Final clique-finding method:</strong>
            <label class="radio">
              <input type="radio" name="cliqueMethod2" value="greedy" checked>
              Greedy (Faster)
            </label>
            <label class="radio">
              <input type="radio" name="cliqueMethod2" value="full">
              Branch and Bound (Exact)
            </label>
        </div>
        <div class="span3">
            <strong>Seed alignment:</strong>
            <label class="radio">
              <input type="radio" name="seed2" id="optionsRadios1" value="NWseed" checked>
              Internally produced alignment
            </label>
            <label class="radio">
              <input type="radio" name="seed2" id="optionsRadios2" value="Manual">
              Upload seed alignment (fasta):
              <button class="btn">Upload</button>
            </label>
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

         <input type="text" class="r3dalign-input-mini" value="0.5">
         <span class="help-inline">Discrepancy (d)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="7">
         <span class="help-inline">Neighborhoods (p)</span>
         <br>

         <input type="text" class="r3dalign-input-mini" value="60">
         <span class="help-inline">Alignment bandwidth (&beta;)</span>
        </div>
        <div class="span3">
            <strong>Final clique-finding method:</strong>
            <label class="radio">
              <input type="radio" name="cliqueMethod3" value="greedy" checked>
              Greedy (Faster)
            </label>
            <label class="radio">
              <input type="radio" name="cliqueMethod3" value="full">
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
          <li><a>16S</a></li>
          <li><a>23S</a></li>
        </ul>
      </div>
      <input type="text" class="" placeholder="Email (optional)"></input>
      <button type="submit" class="btn btn-primary span2 pull-right">Submit</button>
    </div> <!-- row form controls -->

    </form>
    </div> <!-- content -->
  </div> <!-- container -->
</div> <!-- wrap -->

<script type="text/javascript" src="<?php echo base_url(); ?>css/chosen/chosen.jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/main.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/homepage.js"></script>

<script>

$(function() {

    $(".pdb1").chosen().change(function(){
        var div = ".mol1_info";
        var pdb_id = this.options[this.selectedIndex].text;
        UTIL.load_structure_data(div, pdb_id);
    });

    $(".pdb2").chosen().change(function(){
        var div = ".mol2_info";
        var pdb_id = this.options[this.selectedIndex].text;
        UTIL.load_structure_data(div, pdb_id);
    });

    UTIL.bind_events();

    $('.icon-info-sign').tooltip();

});

</script>