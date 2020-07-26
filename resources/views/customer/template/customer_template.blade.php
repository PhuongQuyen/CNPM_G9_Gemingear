<!DOCTYPE html>
<html class="no-js" lang="en">
@include('customer.layout.head')
<body>
@include('customer.layout.login')
@include('customer.layout.menu1')
@yield('slider')
@yield('content')
@include('customer.layout.footer')
@include('customer.layout.bot')
@yield('bot')
</body>
</html>
