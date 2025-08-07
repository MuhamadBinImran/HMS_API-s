<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Expiry Date</th>
        <th>Description</th>
    </tr>
    </thead>
    <tbody>
    @foreach($medicines as $medicine)
        <tr>
            <td>{{ $medicine->name }}</td>
            <td>{{ $medicine->category }}</td>
            <td>{{ $medicine->quantity }}</td>
            <td>{{ $medicine->expiry_date }}</td>
            <td>{{ $medicine->description }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
