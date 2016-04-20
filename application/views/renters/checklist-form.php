<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-check"></i> Rental Home Self-Check
	</div>
	<div class="panel-body">
		<?php echo form_open('renters/checklist_form');	?>
			<div class='table-responsive'>
				<table class="table table-striped table-hover">
					<tr class='bold'>
						<td>Areas</td>
						<td class='success text-center'>Good</td>
						<td class='warning text-center'>Fair</td>
						<td class='danger text-center'>Repair</td>
						<td class='default text-center'>NA</td>
						<td>Details</td>
					</tr>
					<tr>
						<td class='bold' colspan='6'  style="background: #FF851B; color: #fff">Interior</td>
					</tr>
					<tr>
						<td>Walls</td>
						<td class="text-center"><input name='walls' type='radio' value='Good' /></td>
						<td class="text-center"><input name='walls' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='walls' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='walls' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='wallsDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Trim</td>
						<td class="text-center"><input name='trim' type='radio' value='Good' /></td>
						<td class="text-center"><input name='trim' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='trim' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='trim' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='trimDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Cabinets</td>
						<td class="text-center"><input name='cabinets' type='radio' value='Good' /></td>
						<td class="text-center"><input name='cabinets' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='cabinets' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='cabinets' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='cabinetsDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Doors</td>
						<td class="text-center"><input name='doors' type='radio' value='Good' /></td>
						<td class="text-center"><input name='doors' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='doors' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='doors' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='doorsDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Window treatments (blinds,etc)</td>
						<td class="text-center"><input name='treaments' type='radio' value='Good' /></td>
						<td class="text-center"><input name='treaments' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='treaments' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='treaments' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='treamentsDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Carpet</td>
						<td class="text-center"><input name='carpet' type='radio' value='Good' /></td>
						<td class="text-center"><input name='carpet' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='carpet' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='carpet' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='carpetDetails' maxlength='200'></textarea></td>
					</tr>	
					<tr>
						<td>Floors</td>
						<td class="text-center"><input name='floors' type='radio' value='Good' /></td>
						<td class="text-center"><input name='floors' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='floors' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='floors' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='floorsDetails' maxlength='200'></textarea></td>
					</tr>			
					<tr>
						<td>Ceilings</td>
						<td class="text-center"><input name='ceilings' type='radio' value='Good' /></td>
						<td class="text-center"><input name='ceilings' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='ceilings' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='ceilings' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='ceilingsDetails' maxlength='200'></textarea></td>
					</tr>	
					<tr>
						<td class='bold' colspan='6'>Mechanical / Fixtures</td>
					</tr>	
					<tr>
						<td>Plumbing</td>
						<td class="text-center"><input name='plumbing' type='radio' value='Good' /></td>
						<td class="text-center"><input name='plumbing' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='plumbing' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='plumbing' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='plumbingDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Faucets</td>
						<td class="text-center"><input name='Faucets' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Faucets' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Faucets' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Faucets' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='faucetsDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Lighting</td>
						<td class="text-center"><input name='Lighting' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Lighting' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Lighting' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Lighting' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='lightingDetails' maxlength='200'></textarea></td>
					</tr>			
					<tr>
						<td>Electrical</td>
						<td class="text-center"><input name='Electrical' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Electrical' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Electrical' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Electrical' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='electricalDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Water Heater</td>
						<td class="text-center"><input name='wHeater' type='radio' value='Good' /></td>
						<td class="text-center"><input name='wHeater' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='wHeater' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='wHeater' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='wheaterDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Furnace / Heat</td>
						<td class="text-center"><input name='Furnace' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Furnace' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Furnace' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Furnace' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='furnaceDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Appliances</td>
						<td class="text-center"><input name='Appliances' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Appliances' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Appliances' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Appliances' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='appliancesDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Air Conditioning</td>
						<td class="text-center"><input name='Air' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Air' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Air' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Air' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='airDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td class='bold' colspan='6'  style="background: #FF851B; color: #fff">Exterior</td>
					</tr>	
					<tr>
						<td>Siding</td>
						<td class="text-center"><input name='Siding' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Siding' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Siding' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Siding' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='sidingDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Paint</td>
						<td class="text-center"><input name='Paint' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Paint' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Paint' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Paint' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='paintDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Windows</td>
						<td class="text-center"><input name='Windows' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Windows' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Windows' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Windows' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='windowsDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Yard</td>
						<td class="text-center"><input name='Yard' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Yard' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Yard' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Yard' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='yardDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Roof</td>
						<td class="text-center"><input name='Roof' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Roof' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Roof' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Roof' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='roofDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Driveway / Parking</td>
						<td class="text-center"><input name='Driveway' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Driveway' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Driveway' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Driveway' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='drivewayDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Garage / Carport</td>
						<td class="text-center"><input name='Garage' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Garage' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Garage' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Garage' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='garageDetails' maxlength='200'></textarea></td>
					</tr>
					<tr>
						<td>Porch / Deck</td>
						<td class="text-center"><input name='Deck' type='radio' value='Good' /></td>
						<td class="text-center"><input name='Deck' type='radio' value='Fair' /></td>
						<td class="text-center"><input name='Deck' type='radio' value='Repair' /></td>
						<td class="text-center"><input name='Deck' type='radio' value='NA' checked /></td>
						<td><textarea class='form-control'  name='deckDetails' maxlength='200'></textarea></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<button type='submit' class='btn btn-primary'>Submit Check-List</button>
	</form>
</div>
</div>