Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'type-daily-perf-website-break-down-tool',
      path: '/type-daily-perf-website-break-down-tool',
      component: require('./components/Tool'),
    },
  ])
})
