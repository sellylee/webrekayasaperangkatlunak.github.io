<!DOCTYPE html>
<html><head>
    <title>...</title>
</head><body>

        <h3 style="text-align: center">Daftar laporan submenu</h3>

        <table class="table">
            <tr>
                <th></th>
                <th>Title</th>
                <th>Menu</th>
                <th>Url</th>
                <th>Icon</th>
                <th>Active</th>
               
            </tr>

    
            

            <?php $i = 1; ?>
            <?php foreach ($subMenu as $sM): 
            ?>

            <tr>
            <th scope="row"><?= $i; ?></th>
                <td><?= $sM->title ?></td>
                <td><?= $sM->menu_id ?></td>
                <td><?= $sM->url ?></td>
                <td><?= $sM->icon ?></td>
                <td><?= $sM->is_active ?></td>
            </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </table>


</body></html>