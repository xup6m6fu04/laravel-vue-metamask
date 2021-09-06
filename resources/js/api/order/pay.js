import axios from 'axios'

export default async function(args, cookie) {
  let status = 0
  let message = ''
  let bitwin = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/order/pay',
      {
        order_id: args.order_id,
        sign: args.sign,
        address: args.address,
      }, {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        bitwin = response.data.bitwin
      }
    })
    .catch(function(error) { // 请求失败处理
      console.log(error)
    })

  return {
    status,
    message,
    bitwin,
  }
}