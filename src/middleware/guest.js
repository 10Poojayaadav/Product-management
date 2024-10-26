export default function guest({ next, store }) {
    if (store.getters["loggedIn"]) {
      return next({
        name: "messages",
      });
    }
    next();
  }
  