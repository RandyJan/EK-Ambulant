@extends('layouts.master')
@section('title','Mealstub')

@section('content')
    @include('alerts.alert')

    <div class="row">
        <div class="col-md-6">
            <div class="widget-profile-1 card">
                <div class="profile border bottom">
                    <!-- <img class="mrg-top-30" src="/assets/images/others/img-10.jpg" alt=""> -->
                    <h4 class="mrg-top-20 no-mrg-btm text-semibold particulars" ></h4>
                    <p class="reference"></p>
                </div>

                    <div class="pdd-horizon-30 pdd-vertical-20">

                        <h5 class="text-semibold mrg-btm-5">Non Modifiable</h5>
                        <table class="table table-hover table-sm">
                            <tbody class="non-modifiable-container">
                                <!-- <tr>
                                    <td>
                                        <i class="text-success">1</i> |
                                        HP Pavilion 15-au103TX 15.6˝ Laptop Red
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i class="text-success">1</i> |
                                        Canon EOS 77D
                                    </td>
                                </tr> -->
                            </tbody>
                        </table>
                        {{--
                        <p>It looks like Sandpeople did this, all right. Look, here are Gaffi sticks, Bantha tracks. It's just...I never heard of them.</p>
                        <div class="mrg-top-30 text-center">
                            <ul class="list-unstyled list-inline">
                                <li class="list-inline-item no-pdd-horizon">
                                    <a href="#" class="btn btn-facebook btn-icon btn-rounded">
                                        <i class="ti-facebook"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item no-pdd-horizon">
                                    <a href="#" class="btn btn-twitter btn-icon btn-rounded">
                                        <i class="ti-twitter"></i>
                                    </a>
                                </li>
                                <li class="list-inline-item no-pdd-horizon">
                                    <a href="#" class="btn btn-google-plus btn-icon btn-rounded">
                                        <i class="ti-google"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        --}}

                    </div>

            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                {{--
                <div class="pdd-vertical-5 pdd-horizon-10 border bottom print-invisible">
                    <ul class="list-unstyle list-inline text-right">
                        <li class="list-inline-item">
                            <a href="#" class="btn text-gray text-hover display-block padding-10 no-mrg-btm" onclick="window.print();">
                                <i class="ti-printer text-info pdd-right-5"></i>
                                <b>Print</b>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-gray text-hover display-block padding-10 no-mrg-btm">
                                <i class="fa fa-file-pdf-o text-danger pdd-right-5"></i>
                                <b>Export PDF</b>
                            </a>
                        </li>
                    </ul>
                </div>
                --}}
                <div class="card-body">
                    <div class="">
                        <div class="row mrg-top-20">
                            <div class="col-md-12">
                                <h5 class="text-semibold mrg-btm-5">Modifiable</h5>
                                <table class="table table-hover">
                                    <tbody class="modifiable-container">
                                        <tr class="modifiable">
                                            <td>
                                                <i class="text-success">1</i> |
                                                p1
                                            </td>
                                        </tr>
                                        <tr class="modifiable">
                                            <td>
                                                <i class="text-success">1</i> |
                                                HP Pavilion 15-au103TX 15.6˝ Laptop Red
                                            </td>
                                        </tr>
                                        <tr class="modifiable">
                                            <td>
                                                <i class="text-success">1</i> |
                                                Canon EOS 77D
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>


                                <div class="row mrg-top-30">
                                    <div class="col-md-12">
                                        <div class="pull-right text-right">
                                            {{--<p>Sub - Total amount: $2,325</p>
                                            <p>vat (10%) : $232 </p>--}}
                                            <hr>
                                            <h3><b>Total :</b> 0.00</h3>
                                        </div>
                                    </div>
                                </div>

                                {{--
                                <div class="row mrg-top-30">
                                    <div class="col-md-12">
                                        <div class="border top bottom pdd-vertical-20">
                                            <p class="text-opacity"><small>In exceptional circumstances, Financial Services can provide an urgent manually processed special cheque. Note, however, that urgent special cheques should be requested only on an emergency basis as manually produced cheques involve duplication of effort and considerable staff resources. Requests need to be supported by a letter explaining the circumstances to justify the special cheque payment.</small></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mrg-vertical-20">
                                    <div class="col-md-6">
                                        <img class="img-responsive text-opacity mrg-top-5" width="100" src="assets/images/logo/logo.png" alt="">
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <small><b>Phone:</b> (123) 456-7890</small>
                                        <br>
                                        <small>support@themenate.com</small>
                                    </div>
                                </div>
                                --}}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade modal-fs" id="modal-fs" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body height-100">
                    <div class="vertical-align text-center">
                        <div class="table-cell">
                            <div class="container">
                                <div class="row">

                                    <div class="col boxx">

                                        &nbsp;

                                    </div>

                                    {{--
                                    <div class="col-md-4 mr-auto ml-auto">
                                        <div class="pdd-horizon-30 pdd-btm-50">
                                            <img class="img-responsive mrg-horizon-auto" src="/assets/images/others/mailing.png" alt="">
                                            <h4 class="mrg-top-20">We'ill launch soon!</h4>
                                            <p class="mrg-btm-15">Subscribe us</p>
                                            <form class="ng-pristine ng-valid">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">@</span>
                                                        <input type="email" class="form-control" placeholder="Email Adress">
                                                    </div>
                                                </div>
                                                <button class="btn btn-info btn-block text-bold text-uppercase">Sign Up</button>
                                            </form>
                                            <small>No worries, we won't spam</small>
                                        </div>
                                    </div>
                                    --}}

                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="modal-close" href="#" data-dismiss="modal">
                        <i class="ti-close"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('css')
<style>
    .modifiable{
        cursor: pointer;
    }
</style>
@endsection

@section('js')

    <script>

        $(document).ready(function(){
            getMealstubInformation();
        });


        function getMealstubInformation(){
            var data = {
                ref: "{{ $ref }}"
            };

            post('/mealstub/get-info', data, function(res){
                console.log(res);

                $('.particulars').text(res.data.mealstub.PARTICULARS);
                $('.reference').text(res.data.mealstub.REFERENCE_ID);

                var nm_container = $('.non-modifiable-container');
                nm_container.empty();

                var m_container = $('.modifiable-container');
                m_container.empty();

                $.each(res.data.components, function(k,v){
                    if(v.ISMODIFIABLE == 0){
                        nm_container.append(`
                            <tr>
                                <td>
                                    <i class="text-success">${v.QTY}</i> |
                                    ${v.PRODUCTDESC}
                                </td>
                            </tr>
                        `);
                    }

                    if(v.ISMODIFIABLE == 1){
                        m_container.append(`
                            <tr class="modifiable">
                                <td>
                                    <i class="text-success">${v.QTY}</i> |
                                    ${v.PRODUCTDESC}
                                </td>
                            </tr>
                        `);
                    }
                });

                $('.modifiable').on('click', function(){
                    var self = $(this);
                    console.log('clicked..');
                    $('#modal-fs').modal('toggle');
                });
            });


        }

    </script>

@endsection
