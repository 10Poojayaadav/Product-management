// history.js
import { reactive } from 'vue';

// Make the navigation history reactive using reactive()
export const navigationHistory = reactive({
  stack: []  // This will keep track of the route history
});

// Function to add the current route to history
export const addRouteToHistory = (route, extraData = {}) => {
  navigationHistory.stack.push({
    route,
    data: extraData  // Store additional data if needed
  });
};

// Function to remove the last route from the history
export const popRouteFromHistory = () => {
  return navigationHistory.stack.pop();
};

// Function to get the last route in the history without removing it
export const getLastRoute = () => {
  return navigationHistory.stack[navigationHistory.stack.length - 1];
};
