<!DOCTYPE html>
<html><head>
    <title>...</title>
</head><body>

        <h3 style="text-align: center">Daftar laporan User</h3>

        <table class="table">
            <tr>
                <th></th>
                <th>Name</th>
                <th>Email</th>
                <th>Jurusan</th>
               
            </tr>

    
            

            <?php $a = 1; ?>
            <?php foreach ($report as $re): 
            ?>

            <tr>
            <th scope="row"><?= $a; ?></th>
                <td><?= $re->name ?></td>
                <td><?= $re->email ?></td>
                <td><?= $re->jurusan ?></td>
            </tr>
            <?php $a++; ?>
            <?php endforeach; ?>
        </table>


</body></html>