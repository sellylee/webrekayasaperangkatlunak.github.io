<div class="container">
   <div class="row mt-3">
       <div class="col-md-6">

       <div class="card">
            <div class="card-header">
               Form Edit Sub Menu
            </div>
            <div class="card-body">
          
                <!-- form title -->
        <form action="" method="post"> 
        <input type="hidden" name="id" value="<?= $menu['id']; ?>">

        <div class="form-group">
          <label for="title">Edit submenu Title</label>
              <input type="text" class="form-control" id="title" name="title"
              value="<?= $menu['title']; ?>" placeholder=" Edit title">
              <small class="form-text text-danger"><?= form_error('title'); ?></small>
        </div>

                   <!-- form menu -->
        <!-- <div class="form-group">
                <label for="menu">Edit submenu Menu</label>
                <select class="form-control" id="menu" name="menu">
                    <?php foreach ( $menu as $m) : ?>
                    <!-- <?php if( $m == $m['menu'] ) : ?> -->
                    <option value="<?= $m; ?>" selected><?= $m; ?></option>
               <?php else : ?>
                <option value="<?= $m; ?>"><?= $m; ?></option>
               <!-- <?php endif; ?> --> 
               <?php endforeach; ?>
            </select>
            </div> -->

        <div class="form-group">
          <label for="menu">Edit submenu url</label>
              <input type="text" class="form-control" id="url" name="url"
              value="<?= $menu['url']; ?>" placeholder=" Edit url">
              <small class="form-text text-danger"><?= form_error('url'); ?></small>
        </div>
        <button type="submit" name="editsub" class="btn btn-primary float-right">Edit submenu</button>
         </form>
        </div>
      </div>
      
      </div>
  </div>
</div>

       
   