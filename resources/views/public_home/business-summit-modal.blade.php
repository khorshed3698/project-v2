{{-- <style>
.modal-dialog{
  width: 60%;
}
.modal-content {
  background-color: white;
  padding: 5px;
  border-radius: 5px;
  text-align: center;
  position: relative;
  max-width: 100%;
  max-height: 100%;
}

.btn-close {
  position: absolute;
  top: 5px;
  right: 5px;
  font-size: 18px;
  background-color: red;
  border: none;
  cursor: pointer;
  border-radius: 15%;
}

img {
  max-width: 100%;
}

</style>

<div class="row">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
                <a class="link" href="https://bdbusinesssummit.com/" title="Bangladesh Business Summit 2023">
                    <img src="{{url('/assets/images/business_summit2023.png')}}" alt="Bangladesh Business Summit 2023">
                </a>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function() {
        setTimeout(function() {
            $('#myModal').modal('show');
        }, 3000);
        
        $('.btn-close').click(function() {
            $('#myModal').modal('hide');
        });
    });
    
</script> --}}
