import mintlify from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedmintlify = addPrefix(mintlify, prefix);
  addBase({ ...prefixedmintlify });
};
