import axios from 'axios'

export default async function(cookie) {
  let status = 0
  let message = ''
  let orders = ''

  await axios
    .get(process.env.MIX_APP_URL + '/api/orders',
      {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        orders = response.data.orders
      }
    })
    .catch(function(error) { // 请求失败处理
      console.log(error)
    })

  return {
    status,
    message,
    orders,
  }
}