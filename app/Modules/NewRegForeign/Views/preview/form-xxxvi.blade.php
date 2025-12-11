<h2 align="center">Form XXXVI</h2>
<br>

<div align="center">
Documents (Charter, Statutes or Memorandum and Articles of the Company, <br>
or Other Instrument) constitution or defining the constitution of the Company. <br>
<strong>THE COMPANIES ACT, 1994 </strong> <br> <br>
(See Section 379) <br> <br>

</div>


Name of the Company : {{$appInfo->name_of_entity}} <br>
Presented for filing by : {{$appInfo->filledBy_name}} <br> <br>
The {{$appInfo->name_of_entity}} incorporated in <br> <br>
{{$appInfo->country_origin_name}},

Having a place of business in at   {{$appInfo->address_entity}} <br>
in the Bangladesh presents for filing, pursuant Section 379(1) (a) <br> 
of the Companies Act, 1994, The follows:-  <br>
1* Charter/ Statutes/ Memorandum and Articles of Association   <br>
  (Other instrument to be specified), constituting  <br>
or defining the constitution of the Company and duly certified as required by the  <br>
companies rules, 1941  <br>
2. (If the aforesaid document is not written in the English language), a translation 
thereof, duly certified as required by the Companies Rule, 1941.  <br>  <br>

<div>
	<table>
		<tr>
			<td width="50%">
				Signature or Signatures of any one or more of the persons authorised underSection 379 (1) (d) of the Companies Act, 1941, or some other person in Bangladesh duly authorised by the Company
			</td>
			<td width="50%">
					........<?php
				$path="";
				if ($authorizedPerson->digital_signature !=""){
					$path = 'rjsc_newreg_digital_signature/'. $authorizedPerson->digital_signature;
				}
				?>
				@if($path !="")
					@if(file_exists(public_path().'/'.$path) )
						<img height="80px" width="120px" src="{{$path}}">
					@endif
				@endif....  <br> <br>
				 <br> <br> <br>
				 <br> <br>
			</td>
		</tr>
	</table>
</div>

<br>
 
<p align="center">Dated the .......................day of ...............................20</p>
