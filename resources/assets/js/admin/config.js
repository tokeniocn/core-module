const $config = {
  name: "Laravel",
  api: {
    base: window.location ? window.location.origin : "",
    media: {
      upload: "/api/admin/v1/media/upload",
    },
    auth: {
      logout: "/admin/auth/logout",
    },
    menu: {
      tree: "/api/admin/v1/menu/tree",
    },
  },
  store: {
    // infinite: 无限下拉, page: 分页, data: 普通数据, 自定义函数 ({ state, rootState }, data) => { return data; }
    commitType: "infinite",
    // 默认分页数据结构
    defaultPaginationData: {
      data: [],
      total: 0,
      from: 0,
      to: 0,
      path: null,
      per_page: 15,
      current_page: 0,
      last_page: 1,
      first_page_url: null,
      last_page_url: null,
      next_page_url: null,
      prev_page_url: null,
      has_next_page: true,
    },
  },
  ...(window.G || {}),
};

export default $config;
