import axios from 'axios'

export default async function(account, cookie) {
  let status = 0
  let message = ''
  let balance = ''

  await axios
    .get(process.env.MIX_APP_URL + '/api/player/balance?account=' + account,
      {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        balance = response.data.balance
      }
    })
    .catch(function(error) { // 请求失败处理
      status = error.response.status
      message = error.response.data.message
    })

  return {
    status,
    message,
    balance,
  }
}