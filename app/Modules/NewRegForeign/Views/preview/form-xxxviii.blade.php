

<h2 align="center">Form XXXVIII</h2>
<br>

<div align="center">
Documents (Charter, Statutes or Memorandum and Articles of the Company, <br>
or Other Instrument) constitution or defining the constitution of the Company. <br>
<strong>THE COMPANIES ACT, 1994 </strong> <br> <br>
(See Section 379) <br> <br>

</div>


Name of the Company : {{$appInfo->name_of_entity}} <br>
Return pursuant to Section 379 (I) by- <br>
The {{$appInfo->name_of_entity}}  <br>
incorporation  in counting (country) {{$appInfo->country_origin_name}} <br>
of origin has a place of business in at  <br>
of a list of its Directors and Managers.  <br>
Presented for filing by {{$appInfo->filledBy_name}} <br>
List of Directors and Managers of the    <br>

<table border="1">
    <tr>
        <th style="text-align: center;">SL.</th>
        <th style="text-align: center;">Name of Directors and Managers</th>
        <th style="text-align: center;">Address of director and Manager</th>
        <th style="text-align: center;">Description or occupation of Director and Manager</th>
    </tr>
    <?php
        $i=1;
    ?>
    @foreach($subscribers as $subscriber)
    <tr>
        <td>{{$i++}}</td>
        <td>{{$subscriber->corporation_body_name}}</td>
        <td>{{$subscriber->permanent_address}}</td>
        <td>{{$subscriber->other_occupation}}</td>

    </tr>
    @endforeach

</table>


<div>
	<table>
		<tr>
			<td width="50%">
				Signature or Signatures of any one or more of the persons authorised underSection 379 (1) (d) of the Companies Act, 1941, or some other person in Bangladesh duly authorised by the Company
			</td>
			<td width="50%">
					...<?php
                $path="";
                if ($authorizedPerson->digital_signature !=""){
                    $path = 'rjsc_newreg_digital_signature/'. $authorizedPerson->digital_signature;
                }
                ?>
                @if($path !="")
                    @if(file_exists(public_path().'/'.$path) )
                        <img height="80px" width="120px" src="{{$path}}">
                    @endif
                @endif
                ....  <br>
				 <br>.............................................................. <br>
				 <br>.............................................................. <br>
			</td>
		</tr>
	</table>
</div>

<br>
 
<p align="center">Dated the .......................day of ...............................20</p>