import store from './hooks/store/index'
import {Provider} from 'react-redux'
import {
  BrowserRouter as Router,
  Switch,
  Route,
} from "react-router-dom";

import Login from './Auth/Login';
import Register from './Auth/Register';
import Home from './Home'
function App() {
  return (
    <Router>
      <Provider store={store}>
      <Switch>
        <Route path='/' exact component={Home} />
        <Route path='/login' exact component={Login} />
        <Route path='/register' exact component={Register} />
      </Switch>
                  </Provider>
    </Router>
  );
}

export default App;
