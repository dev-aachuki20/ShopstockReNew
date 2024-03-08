<style>
 #add_newModal {
    z-index: 99999;
}
</style>
</style>
<!-- Start add new product -->
<div class="modal" id="AddnewProductModal" role="dialog" style="margin-top: 100px;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Add Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body ">
            <section class="section">
                <div class="section-body">
                  <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                      <div class="card">
                        <div class="card-body">
                        <form action="{{ route('admin.master.products.store') }}"  id="productFormMain" method="POST"  name="productForm" enctype="multipart/form-data">
                            <div class="row view_model_form">

                            </div>
                        </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>        
        </div>
    </div>
</div>
<!-- End add new product -->