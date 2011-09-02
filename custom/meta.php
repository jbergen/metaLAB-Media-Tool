<div class="my_meta_control">
 
	<p>GSD Research</p>
 
	<label>Curated</label>
 
	<p>
		<input type="checkbox" name="_my_meta[curated]" value="true" <?php if(!empty($meta['curated'])) echo "checked='checked'"; ?>/>
		<span>Is this post curated for final exhibition?</span>
	</p>
	
	<label>Call Number</label>
 
	<p>
		<input type="text" name="_my_meta[callnumber]" value="<?php if(!empty($meta['callnumber'])) echo $meta['callnumber']; ?>"/>
		<span>Enter a call number</span>
	</p>
	
	<label>Date</label>
 
	<p>
		<input id='datepicker' type="text" name="_my_meta[date]" value="<?php if(!empty($meta['date'])) echo $meta['date']; ?>"/>
		<span>Enter a call number</span>
	</p>

</div>