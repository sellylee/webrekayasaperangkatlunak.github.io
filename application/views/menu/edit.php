<div class="container">
   <div class="row mt-3">
       <div class="col-md-6">

       <div class="card">
            <div class="card-header">
               Form Edit Menu
            </div>
            <div class="card-body">
          
                <!-- form menu -->
        <form action="" method="post">
        <input type="hidden" name="id" value="<?= $menu['id']; ?>">
        <div class="form-group">
          <label for="menu">Menu</label>
              <input type="text" class="form-control" id="menu" name="menu" 
              value="<?= $menu['menu']; ?>" placeholder=" Edit menu">
              <small class="form-text text-danger"><?= form_error('menu'); ?></small>
        </div>
        <button type="submit" name="edit" class="btn btn-primary float-right">Edit</button>
         </form>
        </div>
      </div>
      
      </div>
  </div>
</div>

       
   