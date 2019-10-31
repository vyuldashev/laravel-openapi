module.exports = {
  base: '/laravel-openapi/',
  title: 'Laravel OpenAPI',
  description: 'Generate OpenAPI specification for Laravel Applications',
  themeConfig: {
    nav: [
      {text: 'Home', link: '/'},
      {text: 'GitHub', link: 'http://github.com/vyuldashev/laravel-openapi'},
      {
        text: 'Packagist',
        link: 'https://packagist.org/packages/vyuldashev/laravel-openapi',
      },
    ],
    sidebar: [
      '/',
      {
        title: 'Paths',
        collapsable: false,
        children: [
          '/paths/operations',
          '/paths/parameters',
          '/paths/request-bodies',
          '/paths/responses',
        ],
      },
      '/schemas',
    ],
    displayAllHeaders: true,
    sidebarDepth: 2,
  },
};
