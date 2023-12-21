import { MetaMaskProvider } from '@metamask/sdk-react';
import CustomModal from './CustomModal';
import ReactDOM from 'react-dom';

const App = () => (
  <MetaMaskProvider
    sdkOptions={{
      modals: {
        install: ({ link }) => {
          let modalContainer = null;

          return {
            mount: () => {
              modalContainer = document.createElement('div');
              document.body.appendChild(modalContainer);

              ReactDOM.render(
                <CustomModal
                  onClose={() => {
                    ReactDOM.unmountComponentAtNode(modalContainer);
                    modalContainer.remove();
                  }}
                />,
                modalContainer,
              );
            },
            unmount: () => {
              if (modalContainer) {
                ReactDOM.unmountComponentAtNode(modalContainer);
                modalContainer.remove();
              }
            },
          };
        },
      },
    }}
  >
    {/* Other components */}
  </MetaMaskProvider>
);

export default App;
