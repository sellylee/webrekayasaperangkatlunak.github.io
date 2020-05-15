<div class="container">
<div class="container-fluid">
  <div class="flash-data" data-flashdata="<?= $this->session->flashdata('flash'); ?>"></div>
  <?php if ( $this->session->flashdata('flash')) : ?>


  <?php endif; ?>


<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
          <div class="row">
          <div class="col-mb-10">
            <!-- validation form error -->
    <?php if (validation_errors()) : ?>
      <div class="alert alert-danger" role="alert">
        <?= validation_errors();  ?>
    <?php endif; ?>

      </div>

    <?= $this->session->flashdata('message'); ?>

        <a href="" class="btn btn-outline-primary mb-3" data-toggle="modal" data-target="#newReportModal">Add New Report</a>
 
         <div class="dropdown inline ml-2 mb-3">
      <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <img src="https://img.icons8.com/cotton/20/add-folder--v1.png"/> Export
      </button>
      <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <a class="dropdown-item" href="<?= base_url('admin/pdf'); ?>">
        <img src="https://img.icons8.com/color/22/000000/export-pdf.png"/>PDF</a>

        <a class="dropdown-item" href="<?= base_url('admin/excel'); ?>">
        <img src="https://img.icons8.com/color/22/000000/export-excel.png"/>Excel</a>
       
      </div>
</div>

            <table class="table table-hover">
              <thead>
                <tr>
                  
                  <th></th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Jurusan</th>
                  
                </tr>
              </thead>
        </tbody>
        <?php $a = 1; ?>
        <?php foreach($report as $re) : ?>
          <tr>
            <th scope="row"><?= $a; ?></th>
            <td><?= $re['name']; ?></td>
            <td><?= $re['email']; ?></td>
            <td><?= $re['jurusan']; ?></td>

            <td>
          
            <!-- tombol hapus -->
            <a href="<?= base_url(); ?>admin/hapus/<?= $re['id']; ?>" class="  tombol-hapus">
            <img src="https://img.icons8.com/cute-clipart/25/000000/delete-forever.png"/></a>

            </td>
          </tr>
          <?php $a++; ?>
      <?php endforeach; ?>
        </div>
      </div>

    </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="newReportModal" tabindex="-1" role="dialog" aria-labelledby="newReportModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="newReportModalLabel">Add New Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- form menu -->
        <form action="<?= base_url('admin/report'); ?>" method="post">
      <div class="modal-body">
    <div class="form-group">
        <input type="text" class="form-control" id="name" name="name" placeholder="Name..">
  </div>

  <div class="form-group">
        <input type="text" class="form-control" id="email" name="email" placeholder="Email..">
   </div>
  <div class="form-group">
        <input type="text" class="form-control" id="email" name="email" placeholder="Jurusan...">
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


