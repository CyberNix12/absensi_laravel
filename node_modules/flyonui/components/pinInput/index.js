import pinInput from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addComponents, prefix = '' }) => {
  const prefixedpinInput = addPrefix(pinInput, prefix);
  addComponents({ ...prefixedpinInput });
};
