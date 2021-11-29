<table>
    <thead>
    <tr>
        @foreach ($heading as $head)
        <th><?= htmlentities($head) ?></th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $row)
        <tr>
            
            @foreach ($heading as $index=>$count_td)
                <td><?= htmlentities($row[$index]) ?></td>
            @endforeach

        </tr>
    @endforeach
    </tbody>
</table>