<style type="text/css">
    body {
        font-family: "Times New Roman", serif;
        font-size: 8pt;
        margin:0px;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }

    table, th, td {
        border: 1px solid black;
        font-style: normal;
    }
    th {
        background-color: yellow; /* Set the background color */
        color: #000; /* Set the text color */
        font-style: bold; /* Make the text bold */
        }
</style>

<table aria-label="Detailed Report Data Table">
    <thead>
        <th style="background-color: yellow; color: #fff; font-style: bold;">#</th>
        <th style="background-color: yellow; color: #fff; font-style: bold;">Name</th>
        <th style="background-color: yellow; color: #fff; font-style: bold;">Phone Number</th>
        <th style="background-color: yellow; color: #fff; font-style: bold;">Email Address</th>
        <th style="background-color: yellow; color: #fff; font-style: bold;">Status</th>
    </thead>
    <tbody>
    @foreach($list as $row)
    <tr>
        <td style="text-align: center">{{$row->sl}}</td>
        <td>{{$row->full_name}}</td>
        <td>{{ '&nbsp;'.$row->moblie_no }}</td>       
        <td>{{$row->email}}</td>
        <td>{{$row->status}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
