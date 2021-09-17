import axios from 'axios'

export default async function(args, cookie) {
  let status = 0
  let message = ''
  let order = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/order',
      {
        symbol: args.symbol,
        chain: args.chain,
        amount: args.amount,
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
        order = response.data.order
      }
    })
    .catch(function(error) { // 请求失败处理
      status = error.response.status
      message = error.response.data.message
    })

  return {
    status,
    message,
    order,
  }
}