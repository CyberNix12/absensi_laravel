import shadcn from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedshadcn = addPrefix(shadcn, prefix);
  addBase({ ...prefixedshadcn });
};
