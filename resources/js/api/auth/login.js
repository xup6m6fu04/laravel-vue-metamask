import axios from 'axios'

export default async function(sign, address) {
  let status = 0
  let access_token = ''
  let message = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/auth/login',
      {
        sign: sign,
        address: address
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        access_token = response.data.auth.access_token
      }
    })
    .catch(function(error) { // 请求失败处理
      console.log(error)
    })

  return {
    sign,
    status,
    message,
    access_token
  }
}