<template>
    <Disclosure as="nav" class="bg-gray-400" v-slot="{ open }">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
                    <!-- Mobile menu button-->
                    <DisclosureButton class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <MenuIcon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                        <XIcon v-else class="block h-6 w-6" aria-hidden="true" />
                    </DisclosureButton>
                </div>
                <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                    <div class="flex-shrink-0 flex items-center">
                        <img class="block lg:hidden h-8 w-auto" src="../image/metamask.svg" alt="Workflow" />
                        <img class="hidden lg:block h-8 w-auto" src="../image/metamask.svg" alt="Workflow" />
                    </div>
                </div>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">
                    <div v-show="this.loading === true">
                        <div v-if="!this.validAddress(this.currentAccount)">
                            <button
                                @click="this.handleConnect"
                                type="button"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-black bg-gray-200 border border-transparent rounded-md hover:bg-gray-800 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                            >
                                <span class="pt-0.5">Connect</span>
                            </button>
                        </div>
                        <div v-else>
                            <button
                                @click="this.transferModal = true"
                                type="button"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-black bg-gray-200 border border-transparent rounded-md hover:bg-gray-800 hover:text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500"
                            >
                                <span class="pt-0.5">Transfer</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Disclosure>
    <div class="py-4"></div>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                Account Information
            </h3>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Chain
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ transferChainIdHex(currentChainIdHex) }}
                    </dd>
                </div>
                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Address
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ currentAccount }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="py-4"></div>
    <div class="flex flex-col" v-if="this.showOrder">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Chain
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                To Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
<!--                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">-->
<!--                                Status-->
<!--                            </th>-->
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Etherscan
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="order in this.orders" :key="order.id">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="../image/eth-icon.png" alt="" />
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ this.transferChainIdHex(order.chain_id) }}
                                        </div>
<!--                                        <div class="text-sm text-gray-500">-->
<!--                                            {{ this.transferChainIdHex(order.chain_id) }}-->
<!--                                        </div>-->
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ order.to_address }}</div>
<!--                                <div class="text-sm text-gray-500">{{ order.from_address }}</div>-->
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ order.amount }}
                            </td>
<!--                            <td class="px-6 py-4 whitespace-nowrap">-->
<!--                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">-->
<!--                                        Active-->
<!--                                    </span>-->
<!--                            </td>-->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a :href="this.chainIdHexToUrl(order.chain_id) + order.tx_hash" class="text-indigo-600 hover:text-indigo-900">LINK</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--Modal-->
    <TransitionRoot as="template" :show="this.transferModal">
        <Dialog as="div" auto-reopen="true" class="fixed z-10 inset-0 overflow-y-auto" @close="open = false">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <TransitionChild
                    as="template"
                    enter="ease-out duration-300"
                    enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enter-to="opacity-100 translate-y-0 sm:scale-100"
                    leave="ease-in duration-200"
                    leave-from="opacity-100 translate-y-0 sm:scale-100"
                    leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <!--                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">-->
                                <!--                                    <ExclamationIcon class="h-6 w-6 text-red-600" aria-hidden="true"/>-->
                                <!--                                </div>-->
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <DialogTitle as="h3" class="text-lg leading-6 font-medium text-gray-600 pb-1">
                                        Address
                                    </DialogTitle>
                                    <div>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">
                                                    Ξ
                                                </span>
                                            </div>
                                            <input type="text" v-model="this.address" name="address" id="address" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="Address" />
                                        </div>
                                    </div>
                                    <div class="pt-3 pb-3" v-show="this.addressError === true">
                                        <div class="border-red-400 text-red-700 px-1 py-0.5 rounded relative" role="alert">
                                            <strong class="font-bold">Oops!  </strong>
                                            <span class="block sm:inline">address is not valid</span>
                                        </div>
                                    </div>

                                    <DialogTitle as="h3" class="pt-6 text-lg leading-6 font-medium text-gray-600 pb-1">
                                        Amount
                                    </DialogTitle>
                                    <div>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">
                                                    Ξ
                                                </span>
                                            </div>
                                            <input type="text" name="amount" id="amount" v-model="this.amount" @keypress="isNumber($event)" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md" placeholder="0.00" />
                                            <div class="absolute inset-y-0 right-0 flex items-center">
                                                <label for="currency" class="sr-only">Currency</label>
                                                <select id="currency" name="currency" class="focus:ring-indigo-500 focus:border-indigo-500 h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-gray-500 sm:text-sm rounded-md">
                                                    <option>ETH</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-3 pb-3" v-show="this.amountError === true">
                                        <div class="border-red-400 text-red-700 px-1 py-0.5 rounded relative" role="alert">
                                            <strong class="font-bold">Oops!  </strong>
                                            <span class="block sm:inline">amount is not valid</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button
                                type="button"
                                @click="this.handleTransfer"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                Transfer
                            </button>
                            <button
                                type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                @click="this.closeTransfer"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script>
import MetaMaskOnboard from "@metamask/onboarding";
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { BellIcon, MenuIcon, XIcon } from "@heroicons/vue/outline";
import { PaperClipIcon } from "@heroicons/vue/solid";
import detectEthereumProvider from "@metamask/detect-provider";
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
import { ExclamationIcon } from "@heroicons/vue/outline";
import ethereum_address from 'ethereum-address'
import * as Vue from 'vue' // in Vue 3
import axios from 'axios'
import VueAxios from 'vue-axios'

export default {
    components: {
        Disclosure,
        DisclosureButton,
        DisclosurePanel,
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        BellIcon,
        MenuIcon,
        XIcon,
        PaperClipIcon,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        ExclamationIcon,
    },
    data() {
        return {
            timer: '',
            loading: false,
            currentAccount: null,
            currentChainIdHex: null,
            transferModal: false,
            address: "",
            amount: 0,
            addressError: false,
            amountError: false,
            info: '',
            orders: [],
            showOrder: false,
        };
    },
    created() {
        this.detect();
    },
    watch: {
        currentAccount() {
            console.log(this.currentAccount);
        },
    },
    mounted() {
        this.$nextTick(() => {
            setTimeout(() => {
                this.loading = true;
                this.getOrders();
            }, 500);
        });
    },
    methods: {
        detect: async function () {
            const provider = await detectEthereumProvider();
            if (provider && provider.isMetaMask) {
                // From now on, this should always be true:
                // provider === window.ethereum
                this.currentChainIdHex = await provider.request({ method: "eth_chainId" });
                provider.on("chainChanged", this.handleChainChanged);
                provider
                    .request({ method: "eth_accounts" })
                    .then(this.handleAccountsChanged)
                    .catch((err) => {
                        // Some unexpected error.
                        // For backwards compatibility reasons, if no accounts are available,
                        // eth_accounts will return an empty array.
                        console.error(err);
                    });
                provider.on("accountsChanged", this.handleAccountsChanged);
                provider.on("disconnect", this.handleDisconnect);
            } else {
                this.setVariableNull();
                console.log("Please install only MetaMask.");
            }
        },
        getOrders: function () {
            let url = process.env.MIX_APP_URL
            if (url === undefined) {
                url = ''
            }
            axios
                .get(url + '/api/orders?from_address=' + this.currentAccount)
                .then(response => {
                    this.orders = response.data.data
                    this.showOrder = true;
                });
        },
        handleChainChanged: function (_chainId) {
            // We recommend reloading the page, unless you must do otherwise
            window.location.reload();
        },
        handleDisconnect: function (_error) {
            // We recommend reloading the page, unless you must do otherwise
            window.location.reload();
        },
        handleAccountsChanged: function (accounts) {
            if (accounts.length === 0) {
                // MetaMask is locked or the user has not connected any accounts
                console.log("Please connect to MetaMask.");
                this.setVariableNull();
            } else if (accounts[0] !== this.currentAccount) {
                this.currentAccount = accounts[0];
                // Do any other work!
            }
        },
        handleConnect: async function () {
            const provider = await detectEthereumProvider();
            provider
                .request({ method: "eth_requestAccounts" })
                .then(this.handleAccountsChanged)
                .catch((err) => {
                    if (err.code === 4001) {
                        // EIP-1193 userRejectedRequest error
                        // If this happens, the user rejected the connection request.
                        this.setVariableNull();
                    } else {
                        this.setVariableNull();
                        console.error(err);
                    }
                });
        },
        handleTransfer: function () {
            this.addressError = false;
            this.amountError = false;
            if (!this.validAddress(this.address)) {
                this.addressError = true;
                return false;
            }
            if(this.amount <= 0) {
                this.amountError = true;
                return false;
            }
            const transactionParameters = {
                to: this.address, // Required except during contract publications.
                from: this.currentAccount, // must match user's active address.
                value: '0x' + (this.amount * 1000000000000000000).toString(16), // Only required to send ether to the recipient from the initiating external account.
                chainId: this.currentChainIdHex, // Used to prevent transaction reuse across blockchains. Auto-filled by MetaMask.
            };
            this.transfer(transactionParameters)
        },
        transfer: async function(transactionParameters) {
            const provider = await detectEthereumProvider()
            const txHash = await provider.request({
                method: 'eth_sendTransaction',
                params: [transactionParameters],
            })
                .then((txHash) => {
                    let url = process.env.MIX_APP_URL
                    if (url === undefined) {
                        url = ''
                    }
                    axios
                        .post(url + '/api/order',
                            {
                                chain_id: this.currentChainIdHex,
                                from_address: this.currentAccount,
                                to_address: this.address,
                                tx_hash: txHash,
                                amount: this.amount,
                            })
                        .then(response => {
                            this.transferModal = false;
                            this.addressError = false;
                            this.amountError = false;
                        })
                        .catch(function (error) { // 请求失败处理
                            console.log(error);
                        });

                })
                .catch((error) => {
                    console.log(error)
                })
        },
        closeTransfer: function () {
            this.addressError = false;
            this.amountError = false;
            this.transferModal = false
        },
        setVariableNull: function () {
            this.currentAccount = null;
        },
        transferChainIdHex: function (chainIdHex) {
            let chain = chainIdHex;
            switch (chainIdHex) {
                case "0x1":
                    chain = chainIdHex + " (Ethereum Main Network)";
                    break;
                case "0x3":
                    chain = chainIdHex + " (Ropsten Test Network)";
                    break;
                case "0x4":
                    chain = chainIdHex + " (Rinkeby Test Network)";
                    break;
                case "0x5":
                    chain = chainIdHex + " (Goerli Test Network)";
                    break;
                case "0x2a":
                    chain = chainIdHex + " (Kovan Test Network)";
                    break;
            }
            return chain;
        },
        chainIdHexToUrl: function (chainIdHex) {
            let chain = chainIdHex;
            switch (chainIdHex) {
                case "0x1":
                    chain = "https://etherscan.io/tx/";
                    break;
                case "0x3":
                    chain = "https://ropsten.etherscan.io/tx/"
                    break;
                case "0x4":
                    chain = "https://rinkeby.etherscan.io/tx/"
                    break;
                case "0x5":
                    chain = "https://goerli.etherscan.io/tx/"
                    break;
                case "0x2a":
                    chain = "https://kovan.etherscan.io/tx/"
                    break;
            }
            return chain;
        },
        installMetaMask: function () {
            const onboard = new MetaMaskOnboard();
            onboard.startOnboarding();
        },
        validAddress: function(address) {
            return ethereum_address.isAddress(address)
        },
        isNumber: function(evt) {
            evt = (evt) ? evt : window.event;
            let charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
            } else {
                return true;
            }
        }
    },
};
</script>
