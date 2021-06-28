import React from 'react';
import {NavigationContainer} from '@react-navigation/native';
import {createStackNavigator} from '@react-navigation/stack';
import store from 'hooks/store';
import {Provider} from 'react-redux'

//Screens
import LoginScreen from './screens/Auth/Login';
const Stack = createStackNavigator();
const Route = () => {
  return (
    <NavigationContainer>
      <Provider store={store}>
      <Stack.Navigator>
        <Stack.Screen component={LoginScreen} name={'LoginScreen'} />
      </Stack.Navigator>
      </Provider>
    </NavigationContainer>
  );
};

export default Route;
