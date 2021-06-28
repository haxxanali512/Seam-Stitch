import React from 'react';
import {Text, View} from 'react-native';
import {useSelector} from 'react-redux'

const Login = ({params}) => {
  const data=useSelector(state=>state.User);
  console.log(data);
  return(
  <View>
    <Text>Login</Text>
  </View>
);
}
export default Login;
