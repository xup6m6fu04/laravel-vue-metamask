import axios from 'axios'

export default async function(args, cookie) {
  let status = 0
  let message = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/order/paid',
      {
        order_id: args.order_id,
        tx_hash: args.tx_hash
      }, {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
    })
    .catch(function(error) { // 请求失败处理
      status = error.response.status
      message = error.response.data.message
    })

  return {
    status,
    message
  }
}