import axios from 'axios'

export default async function(address) {
  let status = 0
  let message = ''
  let nonce = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/auth/nonce',
      {
        address: address
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        nonce = response.data.nonce
      }
    })
    .catch((error) => {
      console.log(error)
    })

  return {
    status,
    message,
    nonce
  }
}