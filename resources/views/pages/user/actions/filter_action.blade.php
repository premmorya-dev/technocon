  <!--begin::Action-->
  <a href="#" class="btn btn-primary btn-sm dropdown-toggle" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
      Actions
      <i class="ki-duotone ki-down fs-5 ms-1"></i>
  </a>

  <!--begin::Menu-->
  <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">



    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="" class="menu-link px-3 btn-sm " id="filter_btn"> <i class=" fa-solid fa-filter text-primary "></i>&nbsp; Filter</a>
    </div>
    <!--end::Menu item-->

    <!--begin::Menu item-->
    <div class="menu-item px-3">
        <a href="{{ route('user.list') }}" class="menu-link px-3 btn-sm "> <i class="fa-regular fa-circle-check text-danger"></i>&nbsp; Clear</a>
    </div>
    <!--end::Menu item-->