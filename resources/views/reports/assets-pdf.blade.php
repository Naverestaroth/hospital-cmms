<h2>

Hospital CMMS

</h2>

<table border="1" width="100%">

<tr>

<th>Code</th>

<th>Name</th>

<th>Category</th>

</tr>

@foreach($assets as $asset)

<tr>

<td>{{ $asset->asset_code }}</td>

<td>{{ $asset->asset_name }}</td>

<td>{{ $asset->category }}</td>

</tr>

@endforeach

</table>