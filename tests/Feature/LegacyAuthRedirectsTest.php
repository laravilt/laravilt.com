<?php

test('legacy auth pages redirect to the panel login', function (string $uri) {
    $this->get($uri)->assertRedirect('/admin/login');
})->with([
    '/login',
    '/register',
    '/forgot-password',
    '/reset-password',
    '/reset-password/some-token',
    '/email/verify',
    '/two-factor-challenge',
    '/user/confirm-password',
]);

test('guests hitting protected routes end up at the panel login', function () {
    $this->post('/demo/reset')->assertRedirect('/login');
    $this->get('/login')->assertRedirect('/admin/login');
});
