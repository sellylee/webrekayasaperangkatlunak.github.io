 <!-- Begin Page Content -->
 <div class="container-fluid">
  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
  <?php if ( $this->session->flashdata('flash')) : ?>


  <?php endif; ?>

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


    <div class="row">
    <div class="col-lg-12">
<!-- validation form error -->
    <?php if (validation_errors()) : ?>
      <div class="alert alert-danger" role="alert">
        <?= validation_errors();  ?>
    <?php endif; ?>

      </div>


    <?= $this->session->flashdata('message'); ?>

        <a href="" class="btn btn-outline-primary mb-4 " data-toggle="modal" data-target="#newSubMenuModal">Add New Submenu</a>

        <div class="dropdown inline ml-2">
      <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <img src="https://img.icons8.com/cotton/20/add-folder--v1.png"/> Export
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <a class="dropdown-item" href="<?= base_url('menu/pdf'); ?>">
        <img src="https://img.icons8.com/color/22/000000/export-pdf.png"/>PDF</a>

        <a class="dropdown-item" href="<?= base_url('menu/excel'); ?>">
        <img src="https://img.icons8.com/color/22/000000/export-excel.png"/>Excel</a>
       
      </div>
</div>

    <table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Title</th>
      <th scope="col">Menu</th>
      <th scope="col">Url</th>
      <th scope="col">Icon</th>
      <th scope="col">Active</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
      <?php $i = 1; ?>
      <?php foreach($subMenu as $sM) : ?>
    <tr>
      <th scope="row"><?= $i; ?></th>
      <td><?= $sM['title']; ?></td>
      <td><?= $sM['menu']; ?></td>
      <td><?= $sM['url']; ?></td>
      <td><?= $sM['icon']; ?></td>
      <td><?= $sM['is_active']; ?></td>
      
      <td>
      <a href="<?= base_url('menu/editsub/') . $sM['id']; ?>" class="btn btn-outline-info ml-2 float-right">
      <img src="https://img.icons8.com/dusk/20/000000/change-user-male.png"/></a>

      <a href="<?= base_url('menu/hapusub/') . $sM['id']; ?>" class="btn btn-outline-danger ml-2 float-right tombol-hapus" 
      ><img src="https://img.icons8.com/cute-clipart/20/000000/delete-forever.png"/></a>
    
      </td> 
    </tr>
      <?php $i++; ?>
      <?php endforeach; ?>
  </tbody>
</table>

    </div>
</div>


</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<!-- modal -->


<!-- Modal -->
<div class="modal fade" id="newSubMenuModal" tabindex="-1" role="dialog" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newSubMenuModalLabel">Add New Sub Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form menu -->
        <form action="<?= base_url('menu/submenu'); ?>" method="post">
      <div class="modal-body"> 
    <div class="form-group">
        <input type="text" class="form-control" id="title" name="title" placeholder="Submenu title">
  </div>

        <div class="form-group">
        <select name="menu_id" id="menu_id" class="form-control">
        <option value="">Select Menu</option>
        <?php foreach ($menu as $m) : ?>
          <option value="<?= $m['id']; ?>"><?= $m['menu']; ?>
        </option>
        <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <input type="text" class="form-control" id="url" name="url" placeholder="Submenu url">
   </div>
       <div class="form-group">
        <input type="text" class="form-control" id="icon" name="icon" placeholder="Submenu icon">
   </div>
          <div class="form-group">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
              <label for="form-check-label" for="is_active">Active ?</label>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Add</button>
      </div>
      </form>

    </div>
  </div>
</div>
